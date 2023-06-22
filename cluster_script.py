from time import sleep
import pandas as pd
import numpy as np
from sklearn.cluster import KMeans
from sklearn.preprocessing import StandardScaler
from joblib import dump, load
import random
import json
import sys


def generate_new_columns_value(base, suffix):
    combinations = [(i, base - i) for i in range(int(base/2) + 1, base + 1)]
    
    if suffix == 'a':
        valid_combinations = [comb if comb[0] > comb[1] else comb[::-1] for comb in combinations]
    else:  # suffix == 'b'
        valid_combinations = [comb if comb[0] < comb[1] else comb[::-1] for comb in combinations]

    chosen_combination = random.choice(valid_combinations)

    return (abs(chosen_combination[0]), abs(chosen_combination[1]))

def apply_new_columns(df, columns):
    for column in columns:
        df[[f'{column}_1', f'{column}_2']] = df[column].apply(
            lambda value: pd.Series(generate_new_columns_value(int(value[:-1]), value[-1]))
        )
    return df

# Reemplaza esto con tu JSON real
json_string = sys.argv[1]

# Convertir la cadena JSON en un diccionario de Python
data_dict = json.loads(json_string)

# Convertir el diccionario en un DataFrame
new_data = pd.DataFrame([data_dict])

# Nombres de las columnas que queremos dividir
columns_to_split = ['act_ref', 'sen_int', 'vis_vrb', 'seq_glo']

# Aplicamos la función a las columnas deseadas
new_data = apply_new_columns(new_data, columns_to_split)

# Definir el diccionario de reemplazo
replacement_dict = {
    "act_ref_1": "active",
    "act_ref_2": "reflexive",
    "sen_int_1": "sensitive",
    "sen_int_2": "intuitive",
    "vis_vrb_1": "visual",
    "vis_vrb_2": "verbal",
    "seq_glo_1": "sequential",
    "seq_glo_2": "global"
}

# Reemplazar los nombres de las columnas
new_data.rename(columns=replacement_dict, inplace=True)

new_data['processing'] = new_data[['active', 'reflexive']].idxmax(axis=1)
new_data['perception'] = new_data[['visual', 'verbal']].idxmax(axis=1)
new_data['input'] = new_data[['sensitive', 'intuitive']].idxmax(axis=1)
new_data['understading'] = new_data[['sequential', 'global']].idxmax(axis=1)

# Definir la tabla Recurso
recurso_df = pd.DataFrame({
    'Recurso': ['mapa_conceptual', 'diagrama_de_flujo', 'lectura', 'audio', 'infografia', 'video_tutorial', 'videoconferencia', 'animación', 'simulación', 'presentación', 'diario', 'busqueda', 'laboratorio_de_programación', 'debate', 'proyecto', 'escrito', 'cuestionario', 'quiz'],
    'active': [2,0,2,0,0,1,1,0,1,1,0,2,1,2,1,0,2,2],
    'reflexive': [0,0,1,0,0,1,1,0,0,0,2,2,1,1,1,0,2,2],
    'sensitive': [0,0,1,0,0,1,2,1,2,1,0,0,2,1,2,0,0,0],
    'intuitive': [0,0,2,0,0,0,2,0,2,1,0,2,1,0,0,0,2,2],
    'visual': [2,2,1,0,2,2,1,1,2,2,0,0,1,0,1,2,0,0],
    'verbal': [0,0,2,2,0,1,2,0,0,0,0,0,1,2,1,0,0,0],
    'sequential': [0,0,1,0,0,2,0,1,1,2,0,0,1,1,2,0,0,0],
    'global': [0,0,2,1,1,0,1,2,1,1,0,0,1,0,1,2,0,0],
})

# Establecer 'Recurso' como el índice del dataframe
recurso_df.set_index('Recurso', inplace=True)

# Asigna los valores de los recursos según los estilos de aprendizaje identificados
for recurso in recurso_df.index:
    new_data[recurso] = new_data['processing'].map(recurso_df.loc[recurso]) + new_data['perception'].map(recurso_df.loc[recurso]) + new_data['input'].map(recurso_df.loc[recurso]) + new_data['understading'].map(recurso_df.loc[recurso])

# Definir las columnas a usar
columnas_usar = ['mapa_conceptual', 'diagrama_de_flujo', 'lectura', 'audio', 'infografia', 'video_tutorial', 'videoconferencia', 'animación', 'simulación', 'presentación', 'diario', 'busqueda', 'laboratorio_de_programación', 'debate', 'proyecto', 'escrito', 'cuestionario', 'quiz']

# Carga el modelo y el escalador
kmeans = load('/Applications/MAMP/htdocs/moodle311/blocks/learning_style/kmeans_model.joblib')
scaler = load('/Applications/MAMP/htdocs/moodle311/blocks/learning_style/scaler_model.joblib')

# Normalizar solo las columnas especificadas
new_data_normalized = scaler.transform(new_data[columnas_usar])

# Predecir el clúster
cluster = kmeans.predict(new_data_normalized)

print("El clúster predicho es:", cluster)